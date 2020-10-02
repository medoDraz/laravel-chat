@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Add new user in Chat Room
                    </div>
                    <div class="card-body">

                        <form action="{{ route('add.room.user') }}" >

                            {{ csrf_field() }}

                            <div class="form-group">

{{--                                <select name="user_id" class="form-control mdb-select" multiple="multiple">--}}
{{--                                    <option value="">all users</option>--}}
{{--                                    @foreach ($users as $user)--}}
{{--                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}

                                <label for="emails">Choose Users to add in chat room: </label>
                                <input type="text"  name="users_id" multiple="multiple" list="drawfemails" required class="form-control">

                                <datalist id="drawfemails" multiple="multiple">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" >{{ $user->name }}</option>
                                    @endforeach

                                </datalist>
                                <input type="hidden" id="room_id"  name="room_id" value="{{ $room->id}}"/>
                                <input type="hidden"id="room_name"  name="room_name" value="{{ $room->name}}"/>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i
                                        class="fa fa-plus"></i> Add User</button>
                            </div>
                        </form><!-- end of form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

