jQuery(document).ready(function($) {

  if ($('#formation-form-html').length) {
    wp.codeEditor.initialize( "formation-form-html", {type:'text/html'} );
  }
  if ($('#formation-confirmation-html').length) {
    wp.codeEditor.initialize( "formation-confirmation-html", {type:'text/html'} );
  }

  // Disable inputs, textareas, and buttons.
  if (typeof disable !== 'undefined') {
    $('input, textarea, button').prop('disabled', true);

    // Loop through the input.data object and populate the input fields.
    for (var key in input) {
      $el = $('#data\\[' + key + '\\]');

      // Expand the height of textareas to fit the contents.
      if ($el.is('textarea')) {
        $el.val(input[key].replace(/\\'/g, "'")).height($el.prop('scrollHeight'));
      } else {
        $el.val(input[key].replace(/\\'/g, "'"));
      }
    }
  }

  // Confirm before deleting a Form.
  $('#delete_form').on('click', function(e) {
    e.preventDefault();

    if (confirm('Really delete this form?')) {
      // Add a hidden field with the delete_form name to fix the preventDefault() above.
      var $form = $('#formation_form');
      $form.append('<input type="hidden" name="delete_form" id="delete_form" value="Delete Form" />');
      $form.submit();
    } else {
      return false;
    }
  })
});
