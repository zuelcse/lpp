<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" href="{{ asset(mix('assets/vendor/fonts/boxicons.css')) }}" />

<!-- Core CSS -->
<link rel="stylesheet" href="{{ asset(mix('assets/vendor/css/core.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('assets/vendor/css/theme-default.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('assets/css/demo.css')) }}" />
<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')) }}" />
<style type="text/css">
	input[type=checkbox]{
		height: 20px;
		width: 20px;
	}

	/* Global Mobile & Android WebView Responsiveness */
	html, body {
		max-width: 100%;
		overflow-x: hidden;
		-webkit-tap-highlight-color: transparent;
	}

	.layout-wrapper,
	.layout-container,
	.layout-page,
	.content-wrapper {
		max-width: 100%;
		overflow-x: hidden;
	}

	.table-responsive {
		max-width: 100%;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
	}

	.select2-container {
		width: 100% !important;
		max-width: 100% !important;
	}

	.select2-container .select2-selection--single {
		min-height: 36px;
		display: flex;
		align-items: center;
	}

	.select2-container--default .select2-selection--single .select2-selection__rendered {
		line-height: 1.4;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		width: 100%;
	}

	.select2-container--default .select2-selection--single .select2-selection__arrow {
		height: 34px;
	}

	.pagination {
		flex-wrap: wrap;
		gap: 0.25rem;
	}

	@media (max-width: 991.98px) {
		.card-header {
			flex-wrap: wrap;
			gap: 0.5rem;
		}
	}

	@media (max-width: 767.98px) {
		.container,
		.container-xxl,
		.container-fluid {
			padding-left: 0.65rem !important;
			padding-right: 0.65rem !important;
		}

		.container-p-y {
			padding-top: 0.75rem !important;
			padding-bottom: 0.75rem !important;
		}

		.card-body {
			padding: 0.85rem;
		}

		.card-header {
			padding: 0.85rem;
		}

		.modal-dialog {
			margin: 0.5rem;
			max-width: calc(100vw - 1rem) !important;
		}

		/* Make legacy un-breakpointed form columns stack cleanly on phones */
		form .row > .col-6,
		form .row > .col-4,
		form .row > .col-3 {
			flex: 0 0 100%;
			max-width: 100%;
			margin-bottom: 0.4rem;
		}

		/* Keep explicit inline label+input rows legible */
		form .row .row > label[class*="col-"] {
			flex: 0 0 100%;
			max-width: 100%;
			text-align: left !important;
			padding-bottom: 0.15rem;
		}

		form .row .row > div[class*="col-"] {
			flex: 0 0 100%;
			max-width: 100%;
		}

		/* Ensure touch-friendly controls inside Android WebView */
		.btn:not(.btn-xs):not(.btn-icon) {
			min-height: 36px;
		}
	}
</style>

<!-- Vendor Styles -->
@yield('vendor-style')


<!-- Page Styles -->
@yield('page-style')
