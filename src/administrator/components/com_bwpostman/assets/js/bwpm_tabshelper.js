//
// BwPostman Newsletter Component
//
// BwPostman Javascript for tabs.
//
// @version %%version_number%%
// @package BwPostman-Admin
// @author Romana Boldt, Karl Klostermann
// @copyright (C) %%copyright_year%% Boldt Webservice <forum@boldt-webservice.de>
// @support https://www.boldt-webservice.de/en/forum-en/forum/bwpostman.html
// @license GNU/GPL v3, see LICENSE.txt
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

jQuery(document).ready(function(){
	$('.bwp-tabs a').click(function() {
		var layout = $(this).attr('data-layout');
		$('#layout').val(layout);
		$('form').submit();
	});

	function updateModal(selector) {
		var $sel = selector;
		var src = $sel.data('src');
		$('#bwp-modal .modal-title').text($sel.data('title'));
		var windowheight = $(window).height()-225;
		$('#bwp-modal .modal-text').html('<iframe id="frame" src="'+src+'" width="100%" height="'+windowheight+'" frameborder="0"></iframe>');
		$('#bwp-modal .modal-spinner').addClass('hidden');
	}
	$('span.iframe').on('click',function(){
		updateModal($(this));
	});
	$("#bwp-modal").on('hidden.bs.modal', function () {
		$('#bwp-modal .modal-title').empty();
		$('#bwp-modal .modal-text').empty();
		$('#bwp-modal .modal-spinner').removeClass('hidden');
		$('#bwp-modal .modal-footer .counter').empty();
	});
});
