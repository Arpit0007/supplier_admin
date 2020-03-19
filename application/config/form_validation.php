<?php
	
	$config = [
			
				'add_contractor' =>[
								[
									"field" => "phone",
									"label" => "User Name",
									"rules" => "required|is_unique[contractor.mobile]"
								],
								
								
						],
				'add_employee' =>[
						[
							"field" => "username",
							"label" => "User Name",
							"rules" => "required|is_unique[employees.username]"
						],
						
						
				],
				'add_user' =>[
						[
							"field" => "username",
							"label" => "User Name",
							"rules" => "required|is_unique[users.username]"
						]
				],
								
		 
	];
	
