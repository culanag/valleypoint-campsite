@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-5 text-center">
        <!--img class="d-block mx-auto mb-4" alt="" width="72" height="72"-->
            <h2>Add Users</h2>
    </div>
    <div class="col-sm-4 offset-sm-4 text-center">
        <form action="" class="form-inlin justify-content-center">
            <div class="row">
                <div class="form-group col-md-6">
                    <input type="text" required="required" class="form-control" id="inputFirstname" placeholder="First name">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" required="required" class="form-control" id="inputLastname" placeholder="Last name">
                </div>  
            </div> 
            <div class="form-group">
                <input type="text" required="required" class="form-control" id="inputUsername" placeholder="Username">
            </div>
            <div class="form-group">
                <input type="password" required="required" class="form-control" id="inputPassword" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" required="required" class="form-control" id="inputConfirmPassword" placeholder="Confirm Password">
            </div>
            <div class="form-group">
            <input type="text" required="required" class="form-control" placeholder="Contact Number">
            </div>
            <div class="form-group">
                <input type="email" required="required" class="form-control" id="inputEmail" placeholder="Email">
            </div>
            <div class="form-group">
                <select class="custom-select d-block w-100" id="state" required="required">
                    <option value="">Role:</option>
                    <option>General Manager</option>
                    <option>Lodging</option>
                    <option>Cashier</option>
                </select>
            </div> 
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div> 
@endsection