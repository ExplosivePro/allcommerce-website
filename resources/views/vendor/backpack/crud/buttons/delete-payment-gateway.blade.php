 <!-- Edit button group -->
 <a href="javascript:void(0)" onclick="deleteThis(this)" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> {{ trans('backpack::crud.delete') }}</a>

 {{-- Button Javascript --}}
 {{-- - used right away in AJAX operations (ex: List) --}}
 {{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
 @push('after_scripts') @if (request()->ajax()) @endpush @endif
 <script>
  if (typeof deleteThis != 'function') {
    $("[data-button-type=delete]").unbind('click');

   function deleteThis(button) {
     swal({
        title: "Unable to Delete",
        text: "Removals Will Need to Be Made By A Developer",
        icon: "warning",
        buttons: {
        cancel: {
          text: "Ok",
          value: null,
          visible: true,
          className: "bg-secondary",
          closeModal: true,
        }
       },
     });
   }
  }
 </script>
 @if (!request()->ajax()) @endpush @endif
