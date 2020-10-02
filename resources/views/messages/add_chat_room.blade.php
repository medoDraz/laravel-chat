@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Add new Chat Room
                    </div>
                    <div class="card-body">

                        <form action="{{ route('room.chat.add') }}" >

                            {{ csrf_field() }}

                            <div class="form-group">
                                <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                                <label>name:</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name') }}">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i
                                        class="fa fa-plus"></i> Add Room</button>
                            </div>
                        </form><!-- end of form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
