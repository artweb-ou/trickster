window.Accordeon = function(componentElement) {
	var mode = 'hover';
	var items = [];
	var self = this;
	var init = function() {
		if (componentElement.dataset.accordeonMode) {
			mode = componentElement.dataset.accordeonMode;
		}
		var elements = componentElement.querySelectorAll('.accordeon_item');
		for (var i = 0; i < elements.length; i++) {
			items.push(new AccordeonItem(elements[i], self, mode));
		}
	};
	this.openItem = function(newItem) {
		for (var i = 0; i < items.length; i++) {
			if (items[i] === newItem) {
				items[i].open();
			} else {
				items[i].close();
			}
		}
	};
	init();
};

window.AccordeonItem = function(componentElement, parentComponent, mode) {
	var activeClassName = 'accordeon_item_active';
	var titleElement = false;
	var contentElement = false;
	var opened = false;
	var self = this;

	var init = function() {
		if (titleElement = componentElement.querySelector('.accordeon_item_title')) {
			if (contentElement = componentElement.querySelector('.accordeon_item_content')) {
				if (mode === 'hover') {
					eventsManager.addHandler(componentElement, 'mouseenter', interactionHandler);
				} else if (mode === 'click') {
					eventsManager.addHandler(componentElement, 'click', interactionHandler);
				}
			}
		}
		if (componentElement.dataset.opened) {
			opened = true;
			self.open(true);
		}
	};
	var interactionHandler = function() {
		if(opened) {
			self.close();
		} else {
			parentComponent.openItem(self);
		}
	};
	this.open = function(instant) {
		if (!opened) {
			opened = true;
			if (instant) {
				componentElement.classList.add(activeClassName);
				contentElement.style.height = contentElement.scrollHeight + 'px';
			} else {
				componentElement.classList.add(activeClassName);
				TweenLite.to(contentElement, 0.5, {'css': {'height': contentElement.scrollHeight}});
			}
		}
	};
	this.close = function() {
		if (opened) {
			opened = false;
			TweenLite.to(contentElement, 0.3, {'css': {'height': 0}});
			componentElement.classList.remove(activeClassName);
		}
	};
	init();
};