@extends('layouts.app')

@section('content')
    {{-- Section One Begins --}}
    <div class="team-01 container-fluid">
        @include('layouts.includes.navbar')
        <div class="flex-half-center" style="margin-top: 40px;">
            <h1 class="text-white text-playfair underline-light-blue pb-2">Find a Loan Officer or Branch</h1>
        </div>
    </div>
    {{-- Section One Ends --}}

    {{-- Section Two Begins --}}
    <div class="branch-02" style="height: 80vh;">
        <div style="padding-top: 20vh">
            <center>
                <div class="form-group">
                    <div class="row pull-center"  style="background-color: #2e292766; width: 61.803398875vw;">
                        <div class="col-md-12 px-2 py-4 text-playfair text-white text-center">
                            <form method="get" action="/found">
                                <h4><strong>Search By Location, Address, or Zip Code</strong></h4><br>
                                <input type="text" class="form-control my-2" name="location" value="" placeholder="Example: 1600 Pennsylvania Ave">
                                <input type="number" class="form-control my-2" name="number" value="" placeholder="Example: 50" min="0">
                                <select name="measure" class="form-control my-2">
                                    <option value="ML">Miles</option>
                                    <option value="KM">kilometers</option>
                                </select><br><br>
                                <button type="submit" class="text-uppercase light-blue-button">Search</button>
                            </form>
                        </div>
                    </div>
            </center>
        </div>
    </div>
    {{-- Section Two Ends --}}
@endsection