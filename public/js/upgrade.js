$(function(){
	$('#checkall').click(function(e) {
		$('input[type=checkbox]:not(#checkall)').prop('checked', $(e.target).prop('checked'));
	});

	$('.class-checkbox').click(function(e) {
		var id = e.target.id.split(/-/)[2];
		$('input.user-in-class-'+id).prop('checked', $(e.target).prop('checked'));
		e.stopPropagation();
	});

	$('tr.classRow').click(function(e){
		if (e.target.nodeType == 'INPUT') return;

		var id = e.currentTarget.id.split(/-/)[2];

		$('.class-row-'+id).toggle();
		$('.expander', e.currentTarget).toggleClass('open');
		return false;
	})

	var plural = function(num) {
		return (num == 1) ? '' : 's';
	}

	var getSelectedUsers = function() {
		return $('.user-checkbox:checked');
	}

	var updatePricing = function() {
		var users = getSelectedUsers(),
			pricePerUser = (users.length >= discountMinimum) ? discountPricing : singlePricing,
			totalPrice = users.length * pricePerUser;

		$('#totalPrice').html('$'+totalPrice.toFixed(2));

		if (users.length >= discountMinimum) {
			$('#pricePerStudent').html('<strong>$'+pricePerUser.toFixed(2)+'</strong>/'+ text.STUDENT +' ('+users.length+' '+ text.TOTAL +')');
		} else if (users.length == 0) {
			$('#pricePerStudent').html(text.NO_ACCOUNTS_SELECTED);
		} else {
			$('#pricePerStudent').html('<strong>$'+pricePerUser.toFixed(2)+'</strong>/'+ text.STUDENT +' ('+users.length+' '+ text.TOTAL +')<br /><br />Upgrade '+(discountMinimum-users.length)+' more student'+plural(discountMinimum-users.length)+'<br />for a deep discount!');
		}
	}

	$('input[type=checkbox]').click(updatePricing)

	var submitted = false;
	$('#upgradeNowButton').click(function(e){
		if (submitted) return false;

		if (getSelectedUsers().length > 0) {
			submitted = true;
			document.forms.userForm.submit();
		} else {
			alert(text.EMPTY_MSG);
		}
		return false;
	});


	var submitted = false;
	$('#purchase-button').click(function() {
		if (submitted) return;
		submitted = true;
		document.forms.mainForm.submit();
		return false;
	})

	$('.payment-method').click(function(e){
		var table = $('#billing-information');
		if (e.target.value == 'cc') {
			table.show();
		} else {
			table.hide();
		}
	})
});
