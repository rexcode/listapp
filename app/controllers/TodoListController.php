<?php

class TodoListController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('csrf', ['on' => ['post', 'put', 'delete', 'patch']]);
		$this->beforeFilter('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{	
		// $todo_lists = TodoList::all();
		$todo_lists = TodoList::where('user_id', '=', Auth::user()->id)->get();
		return View::make('todos.index')->with('todo_lists', $todo_lists);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('todos.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//Define rules for validation
		$rules = array(
			'name' => ['required', 'unique:todo_lists', 'string']
			);

		// pass rules and input to validator class
		$validator = Validator::make(Input::all(), $rules);

		// test if input fails
		if ($validator->fails()) 
		{	
			// $messages = $validator->messages();
			// return $messages;
			return Redirect::route('todos.create')->withErrors($validator)->withInput();	
		}
		$name = Input::get('name');
		$list = new TodoList();
		$list->name = $name;
		$list->user_id = Auth::user()->id;
		$list->save();
		return Redirect::route('todos.index')->withMessage('List was created!');
	}


	/**
	 * Display the specified resource.
	 * @param  int  $id
	 * @return Response
	 *
	 */
	public function show($id)
	{	
		$list = TodoList::findOrFail($id);
		if($list->user_id == Auth::user()->id)
		{	
		$items = $list->listItems()->get();
		return View::make('todos.show')
			->withList($list)
			->withItems($items);
		} else {
			return Redirect::route('todos.index')->withMessage('You are not authorised to view this list.');
		}
	}

	/**
	 * Show the form for editing the specified 
	 */
	public function edit($id)

	{	
		$list = TodoList::findOrFail($id);
	// *
		if ($list->user_id == Auth::user()->id)
		{
			return View::make('todos.edit')->withList($list);
		}	else {
			return Redirect::route('todos.index')->withMessage('You are not authorised to edit this list.');
		}
		
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$rules = array(
		// return Input::all();
		//Define rules for validation
			'name' => ['required', 'unique:todo_lists', 'alpha_dash']
			);

		// pass rules and input to validator class
		$validator = Validator::make(Input::all(), $rules);

		// test if input fails
		if ($validator->fails()) 
		{	
			// $messages = $validator->messages();
			// return $messages;
			return Redirect::route('todos.edit', $id)->withErrors($validator)->withInput();	
		}

		$name = Input::get('name');
		$list = TodoList::findOrFail($id);
		if($list->user_id == Auth::user()->id)
		{
			$list->name = $name;
			$list->update();
			return Redirect::route('todos.index')->withMessage('List was updated! ');
		} else {
			return Redirect::route('todos.index')->withMessage('You are not authorised to update this list.');
		}
	}
		
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{	// $list =
		$list=TodoList::findOrFail($id);
		if($list->user_id == Auth::user()->id)
		{
			$list->delete();
			return Redirect::route('todos.index')->withMessage("List was deleted.");
		} else {
			return Redirect::route('todos.index')->withMessage('You are not authorised to destroy this list.');
		}
	}

}