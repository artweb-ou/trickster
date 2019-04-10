window.GalleryNextButtonComponent = function(galleryObject) {
	var componentElement;
	var self = this;

	var init = function() {
		createDomStructure();
		eventsManager.addHandler(componentElement, "click", onClick);
	};
	this.destroy = function() {
		eventsManager.removeHandler(componentElement, "click", onClick);
	};
	var createDomStructure = function() {
		componentElement = self.makeElement('div', 'gallery_button_next');
		componentElement.innerHTML = '<span class="gallery_button_text">' + window.translationsLogics.get('gallery.next') + '</span>';
	};
	var onClick = function(event) {
		eventsManager.preventDefaultAction(event);
		eventsManager.cancelBubbling(event);
		galleryObject.stopSlideShow();
		galleryObject.displayNextImage();
	};
	this.getComponentElement = function() {
		return componentElement;
	};
	this.adjustHeight = function(height) {
		componentElement.style.height = height + "px";
	};
	init();
};
DomElementMakerMixin.call(GalleryNextButtonComponent.prototype);