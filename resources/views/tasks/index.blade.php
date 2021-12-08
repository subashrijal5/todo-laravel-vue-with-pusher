<!-- resources/views/chat.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-info">
        <div class="card-header">Tasks</div>
        <div class="card-body">
            <task-list v-on:taskupdate="updateTask" :user="{{ Auth::id() }}" :tasks="todos"></task-list>
        </div>
        <div class="card-footer">
            <create-todo v-on:taskStore="addTask" :user="{{ Auth::user() }}"></create-todo>
        </div>
    </div>
</div>
@endsection
