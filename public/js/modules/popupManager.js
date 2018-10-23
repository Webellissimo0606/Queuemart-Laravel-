class PopupManager {
    constructor($iframe) {
        this._$trigger = $("[data-popup-trigger], .popup__inner");
        this._$content = $(".popup__content");

        this._$trigger.click(this.togglePopup);
        this._$content.click(e => {
            e.stopPropagation();
        });
    }

    togglePopup = e => {
        e.preventDefault();
        const _$this = $(e.currentTarget);
        const _target = _$this.attr("data-popup-trigger");
        const _$target = $(`.popup[data-popup-target=${_target}]`);
        _$target.toggleClass("popup--active");
    }
}