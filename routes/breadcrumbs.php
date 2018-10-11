<?php

// for user
	Breadcrumbs::register('user', function ($breadcrumbs) {
	    $breadcrumbs->push('Users', route('users.index'), ['icon' => '<i class="fa fa-user"></i>']);
	});
	Breadcrumbs::register('create_user', function($breadcrumbs)
	{
		$breadcrumbs->parent('user');
		$breadcrumbs->push('Add Users', route('users.create'));
	});
	Breadcrumbs::register('edit_user', function($breadcrumbs,$user)
	{
		$breadcrumbs->parent('user');
		$breadcrumbs->push(substr($user->firstname,0,50), route('users.index',$user));
	});