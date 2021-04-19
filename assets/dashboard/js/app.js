import jquery from 'jquery'
import 'popper.js'
import 'bootstrap/js/dist/modal'

const createButtons = document.querySelectorAll('[data-create-button]');
if (createButtons.length > 0) {
    import('./modules/modal')
        .then(module => (new module.default()).init(createButtons))
        .catch(e => console.error({e}))
}

const deleteButtons = document.querySelectorAll('[data-delete-button]');
if (deleteButtons.length > 0) {
    import('./modules/delete')
        .then(module => (new module.default(deleteButtons)))
        .catch(e => console.error({e}))
}

const flashes = document.querySelectorAll('[data-flash-alert]');
if (flashes.length > 0) {
    import('./modules/flash')
        .then(module => (new module.default(flashes)))
        .catch(e => console.error({e}))
}
