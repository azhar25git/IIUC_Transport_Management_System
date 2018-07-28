@extends('layouts.userlayout')

@section('usercontent')
    <div class="panel-body" style="background:#212529">
        <h1>Profile</h1>
    </div>
    <hr>
    <div class="panel-body" style="background:#212529">
        <div class="container">
            <div class="userinfo">
                <b><h3>Basic Info:</h3></b>
                <hr>
                <table class="table-active">
                    <thead class="">
                    <tr>
                        <td>
                            Name: {{Auth::user()->name}}
                        </td>
                        <td>
                            Gender: {{Auth::user()->gender == 0 ? 'Male' : 'Female'}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            ID: {{Auth::user()->id}}
                        </td>
                        <td>
                            Registered
                            As: {{Auth::user()->userrole == 1 ? 'Student' : ( Auth::user()->userrole == 2 ? 'Faculty Member' :(Auth::user()->userrole == 3 ? 'Officer/Staff' : 'undefined')) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Email: {{Auth::user()->email}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Contact: {{Auth::user()->contact ? Auth::user()->contact : 'none'}}
                        </td>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br><br>
        <div class="container">
            <div class="userrouteinfo">
                <b><h3>Transport Schedule:</h3></b>
                <hr>
                <table class="table table-bordered">
                    <thead class="">
                    <tr>
                        <td>
                            Day
                        </td>
                        <td>
                            Pick Up Point
                        </td>
                        <td>
                            Pick Up Time
                        </td>
                        <td>
                            Drop Point
                        </td>
                        <td>
                            Leaving Time
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            Saturday
                        </td>
                        <td>
                            Pick Up Point
                        </td>
                        <td>
                            Pick Up Time
                        </td>
                        <td>
                            Drop Point
                        </td>
                        <td>
                            Leaving Time
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Sunday
                        </td>
                        <td>
                            Pick Up Point
                        </td>
                        <td>
                            Pick Up Time
                        </td>
                        <td>
                            Drop Point
                        </td>
                        <td>
                            Leaving Time
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Monday
                        </td>
                        <td>
                            Pick Up Point
                        </td>
                        <td>
                            Pick Up Time
                        </td>
                        <td>
                            Drop Point
                        </td>
                        <td>
                            Leaving Time
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tuesday
                        </td>
                        <td>
                            Pick Up Point
                        </td>
                        <td>
                            Pick Up Time
                        </td>
                        <td>
                            Drop Point
                        </td>
                        <td>
                            Leaving Time
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Wednesday
                        </td>
                        <td>
                            Pick Up Point
                        </td>
                        <td>
                            Pick Up Time
                        </td>
                        <td>
                            Drop Point
                        </td>
                        <td>
                            Leaving Time
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Thursday
                        </td>
                        <td>
                            Pick Up Point
                        </td>
                        <td>
                            Pick Up Time
                        </td>
                        <td>
                            Drop Point
                        </td>
                        <td>
                            Leaving Time
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Friday
                        </td>
                        <td>
                            Pick Up Point
                        </td>
                        <td>
                            Pick Up Time
                        </td>
                        <td>
                            Drop Point
                        </td>
                        <td>
                            Leaving Time
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection
