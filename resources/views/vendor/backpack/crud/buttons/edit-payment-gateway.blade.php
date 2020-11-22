 <!-- Edit button group -->
 <a href="javascript:void(0)" onclick="editEntry(this)" class="btn btn-sm btn-link pr-0"  data-button-type="edit"><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</a>

 {{-- Button Javascript --}}
 {{-- - used right away in AJAX operations (ex: List) --}}
 {{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
 @push('after_scripts') @if (request()->ajax()) @endpush @endif
 <script>
  if (typeof editEntry != 'function') {
    $("[data-button-type=edit]").unbind('click');

   function editEntry(button) {
     swal({
        title: "Unable to Edit",
        text: "Edits Will Need to Be Made By A Developer",
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
