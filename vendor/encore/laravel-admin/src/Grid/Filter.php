<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Filter\AbstractFilter;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

/**
 * Class Filter.
 *
 * @method AbstractFilter     equal($column, $label = '')
 * @method AbstractFilter     notEqual($column, $label = '')
 * @method AbstractFilter     like($column, $label = '')
 * @method AbstractFilter     ilike($column, $label = '')
 * @method AbstractFilter     gt($column, $label = '')
 * @method AbstractFilter     lt($column, $label = '')
 * @method AbstractFilter     between($column, $label = '')
 * @method AbstractFilter     in($column, $label = '')
 * @method AbstractFilter     notIn($column, $label = '')
 * @method AbstractFilter     where($callback, $label)
 * @method AbstractFilter     date($column, $label = '')
 * @method AbstractFilter     day($column, $label = '')
 * @method AbstractFilter     month($column, $label = '')
 * @method AbstractFilter     year($column, $label = '')
 * @method AbstractFilter     hidden($name, $value)
 */
class Filter
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $supports = [
        'equal', 'notEqual', 'ilike', 'like', 'gt', 'lt', 'between',
        'where', 'in', 'notIn', 'date', 'day', 'month', 'year', 'hidden',
    ];

    /**
     * If use id filter.
     *
     * @var bool
     */
    protected $useIdFilter = true;

    /**
     * Id filter was removed.
     *
     * @var bool
     */
    protected $idFilterRemoved = false;

    /**
     * Action of search form.
     *
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $view = 'admin::filter.modal';

    /**
     * @var string
     */
    protected $filterModalId = 'filter-modal';

    /**
     * Create a new filter instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        $pk = $this->model->eloquent()->getKeyName();

        $this->equal($pk, strtoupper($pk));
    }

    /**
     * Set action of search form.
     *
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Set modalId of search form.
     *
     * @param string $filterModalId
     *
     * @return $this
     */
    public function setModalId($filterModalId)
    {
        $this->filterModalId = $filterModalId;

        return $this;
    }

    /**
     * Disable Id filter.
     */
    public function disableIdFilter()
    {
        $this->useIdFilter = false;
    }

    /**
     * Use a custom filter.
     *
     * @param AbstractFilter $filter
     *
     * @return AbstractFilter
     */
    public function use(AbstractFilter $filter)
    {
        return $this->addFilter($filter);
    }

    /**
     * Add a filter to grid.
     *
     * @param AbstractFilter $filter
     *
     * @return AbstractFilter
     */
    protected function addFilter(AbstractFilter $filter)
    {
        $filter->setParent($this);

        return $this->filters[] = $filter;
    }

    /**
     * Execute the filter with conditions.
     *
     * @return array
     */
    public function execute()
    {
        return $this->model->addConditions($this->conditions())->buildData();
    }

    /**
     * Get all conditions of the filters.
     *
     * @return array
     */
    public function conditions()
    {
        $inputs = array_dot(Input::all());

        $inputs = array_filter($inputs, function ($input) {
            return $input !== '' && !is_null($input);
        });

        if (empty($inputs)) {
            return [];
        }

        $params = [];

        foreach ($inputs as $key => $value) {
            array_set($params, $key, $value);
        }

        $conditions = [];

        $this->removeIDFilterIfNeeded();

        foreach ($this->filters() as $filter) {
            $conditions[] = $filter->condition($params);
        }

        return array_filter($conditions);
    }

    /**
     * Remove ID filter if needed.
     */
    public function removeIDFilterIfNeeded()
    {
        if (!$this->useIdFilter && !$this->idFilterRemoved) {
            array_shift($this->filters);
            $this->idFilterRemoved = true;
        }
    }

    /**
     * Get all filters.
     *
     * @return AbstractFilter[]
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     * @param callable $callback
     * @param int $count
     *
     * @return bool
     */
    public function chunk(callable $callback, $count = 100)
    {
        return $this->model->addConditions($this->conditions())->chunk($callback, $count);
    }

    /**
     * Generate a filter object and add to grid.
     *
     * @param string $method
     * @param array $arguments
     *
     * @return AbstractFilter|$this
     */
    public function __call($method, $arguments)
    {
        if (in_array($method, $this->supports)) {
            $className = '\\Encore\\Admin\\Grid\\Filter\\' . ucfirst($method);

            return $this->addFilter(new $className(...$arguments));
        }

        return $this;
    }

    /**
     * Get the string contents of the filter view.
     *
     * @return \Illuminate\View\View|string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get the string contents of the filter view.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $this->removeIDFilterIfNeeded();

        if (empty($this->filters)) {
            return '';
        }

        $script = <<<'EOT'

$("#filter-modal .submit").click(function () {
    $("#filter-modal").modal('toggle');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});

EOT;
        Admin::script($script);

        return view($this->view)->with([
            'action' => $this->action ?: $this->urlWithoutFilters(),
            'filters' => $this->filters,
            'modalId' => $this->filterModalId,
        ]);
    }

    /**
     * Get url without filter queryString.
     *
     * @return string
     */
    protected function urlWithoutFilters()
    {
        $columns = [];

        /** @var Filter\AbstractFilter $filter * */
        foreach ($this->filters as $filter) {
            $columns[] = $filter->getColumn();
        }

        /** @var \Illuminate\Http\Request $request * */
        $request = Request::instance();

        $query = $request->query();
        array_forget($query, $columns);

        $question = $request->getBaseUrl() . $request->getPathInfo() == '/' ? '/?' : '?';

        return count($request->query()) > 0
            ? $request->url() . $question . http_build_query($query)
            : $request->fullUrl();
    }
}
