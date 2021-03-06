@extends('layouts.master')
@section('content')
{{-- <div class="container"> --}}
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="text-center">Todo Lists of '{{{ Auth::user()->username }}}'</h2>
			<p class="text-center"><small >(click on the list name to add tasks to the list)</small></p>
		</div>
		<div class="panel-body">
			
			@if(count($todo_lists) == 0) 
				<p class="text-center">You have no lists.</p> 
			@endif
				
			@foreach($todo_lists as $list )
			<div class="row">
				{{-- show the list name --}}
				<div class="col-md-2 col-md-offset-4 text-center hover-list-name">
					<h4>{{ link_to_route('todos.show', $list->name, [$list->id], ['class' => 'hover', 'title' => 'click to see/add tasks', 'data-toggle' => 'tooltip', 'data-animation'=> 'true', 'data-delay' => '100', 'data-placement' => 'top']) }}</h4>
					{{-- <li>{{ $list->name }}</li> --}}
				</div>
				{{-- empty div for hover --}}
				{{-- <div class="hover-effect col-md-2 text-center">
				</div> --}}
				{{-- show list edit button --}}
				<div class = "col-md-1 text-center">
					{{ link_to_route('todos.edit', 'edit', [$list->id], ['class' => 'btn btn-primary btn-small']) }}
				</div>
				{{-- show list delete/destroy button --}}
				<div class="col-md-1 text-center">
					{{ Form::model($list, ['method' => 'delete', 'route' => ['todos.destroy', $list->id] ]) }}
					{{ Form::button('Delete', ['class' => 'btn btn-danger', 'type' => 'submit']) }}
					{{ Form::close() }}
				</div>
				<div class="col-md-3">
				</div>
			</div>
		<hr>
			@endforeach
		</div>
	
	<div class="panel-footer">
	<p class="text-center">{{ link_to_route('todos.create', 'Create a new list', null, ['class'=>'btn btn-info']) }} </p>
	{{-- {{ link_to_route('register', 'Register to Create a list', null, ['class'=>'btn btn-default']) }} --}}
	</div>
	</div>
	@stop
