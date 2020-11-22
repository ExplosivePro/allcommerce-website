<a href="javascript:void(0)" onclick="addEntry(this)" class="btn btn-primary" data-style="zoom-in" data-button-type="add"><span class="ladda-label"><i class="la la-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name }}</span></a>

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>
	if (typeof addEntry != 'function') {
		$("[data-button-type=add]").unbind('click');

		function addEntry(button) {
			swal({
				title: "Unable to Create",
				text: "New Gateways Require an On-Boarding Process and Must be Seeded into the system by a Developer",
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
