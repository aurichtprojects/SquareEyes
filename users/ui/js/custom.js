$(function() {
	//$(document).on("click", "a#user_list", function(){ getUserList(this); });	
	$(document).on("click", "a#create_user_form", function(){ getCreateForm(this); });	
	$(document).on("click", "button#add_user", function(){ addUser(this); });
	$(document).on("click", "a.delete_confirm", function(){ deleteConfirmation(this); });
	$(document).on("click", "button.delete", function(){ deleteUser(this); });
	$(document).on("dblclick", "td.edit", function(){ makeEditable(this); });
	$(document).on("blur", "input#editbox", function(){ removeEditable(this) });

	getUserList();	
});

function removeEditable(element) { 
	
	$('#indicator').show();
	
	var User = new Object();
	User.id = $('.current').attr('user_id');		
	User.field = $('.current').attr('field');
	User.newvalue = $(element).val();
	
	var userJson = JSON.stringify(User);
	
	$.post('Controller.php',
		{
			action: 'update_field_data',			
			user: userJson
		},
		function(data, textStatus) {
			$('td.current').html($(element).val());
			$('.current').removeClass('current');
			$('#indicator').hide();			
		}, 
		"json"		
	);	
}

function makeEditable(element) {
	field_type = "text";
	$(element).html('<input id="editbox" size="'+  $(element).text().length +'" type="'+field_type+'" value="'+ $(element).text() +'">');  
	$('#editbox').focus();
	$(element).addClass('current'); 
}

function deleteConfirmation(element) {	
	$("#delete_confirm_modal").modal("show");
	$("#delete_confirm_modal input#user_id").val($(element).attr('user_id'));
}

function deleteUser(element) {	
	
	var User = new Object();
	User.id = $("#delete_confirm_modal input#user_id").val();
	
	var userJson = JSON.stringify(User);
	
	$.post('Controller.php',
		{
			action: 'delete_user',
			user: userJson
		},
		function(data, textStatus) {
			getUserList(element);
			$("#delete_confirm_modal").modal("hide");
		}, 
		"json"		
	);	
}

function getUserList(element) {
	
	$('#indicator').show();
	
	$.post('Controller.php',
		{
			action: 'get_users'				
		},
		function(data, textStatus) {
			renderUserList(data);
			$('#indicator').hide();
		}, 
		"json"		
	);
}

function renderUserList(jsonData) {
	
	var table = '<table width="100%" cellpadding="5" class="table table-hover table-bordered"><thead><tr><th scope="col">Name</th><th scope="col">Email</th><th scope="col">Password</th><th scope="col"></th></tr></thead><tbody>';

	$.each( jsonData, function( index, user){     
		table += '<tr>';
		table += '<td class="edit" field="name" user_id="'+user.id+'">'+user.name+'</td>';
		table += '<td class="edit" field="email" user_id="'+user.id+'">'+user.email+'</td>';
		table += '<td class="edit" field="password" user_id="'+user.id+'">'+user.password+'</td>';
		table += '<td><a href="javascript:void(0);" user_id="'+user.id+'" class="delete_confirm btn btn-danger"><i class="icon-remove icon-white"></i></a></td>';
		table += '</tr>';
    });
	
	table += '</tbody></table>';
	
	$('div#content').html(table);
}

function addUser(element) {	
	
	$('#indicator').show();
	
	var User = new Object();
	User.name = $('input#name').val();
	User.email = $('input#email').val();
	User.passwd = $('input#passwd').val();
	
	var userJson = JSON.stringify(User);
	
	$.post('Controller.php',
		{
			action: 'add_user',
			user: userJson
		},
		function(data, textStatus) {
			getUserList(element);
			$('#indicator').hide();
		}, 
		"json"		
	);
}

function getCreateForm(element) {
	var form = '<div class="input-prepend">';
		form +=	'<span class="add-on"> Name</span>';
		form +=	'<input type="text" id="name" name="name" value="" class="input-xlarge" />';		
		form +=	'</div><br/><br/>';

		form +=	'<div class="input-prepend">';
		form +=	'<span class="add-on"> Password</span>';
		form +=	'<input type="text" id="passwd" name="passwd" value="" class="input-xlarge" />';
		form +=	'</div><br/><br/>';

		form +=	'<div class="input-prepend">';
		form +=	'<span class="add-on"> Email</span>';
		form +=	'<input type="text" id="email" name="email" value="" class="input-xlarge" />';
		form +=	'</div><br/><br/>';

		form +=	'<div class="control-group">';
		form +=	'<div class="">';		
		form +=	'<button type="button" id="add_user" class="btn btn-primary"><i class="icon-ok icon-white"></i> Add User</button>';
		form +=	'</div>';
		form +=	'</div>';
		
		$('div#content').html(form);
}