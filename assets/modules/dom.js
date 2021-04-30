export const createLoader = () => {
    const loader = document.createElement('div')
    loader.classList.add('app-loader')
    loader.innerHTML = '<div class="spin"></div>'
    document.body.appendChild(loader)
}

export const removeLoader = () => {
    const loader = document.querySelector('.app-loader')
    document.body.removeChild(loader)
}

/**
 * @param {HTMLElement} element
 * @param {number} speed
 */
export const removeFadeOut = (element, speed = 300) => {
    const seconds = speed / 1000
    element.style.transition = `opacity ${seconds}s ease`
    element.style.opacity = 0
    setTimeout(() => element.parentNode.removeChild(element), speed)
}

/**
 * @param {HTMLElement} element
 * @param {string} loadingText
 */
export const createSectionLoader = (element, loadingText = 'chargement...') => {
    const loader = document.createElement('div')
    loader.innerHTML = `
        <div class="text-center">
           <div class="spinner-border text-primary"></div>
           <div>${loadingText}</div>
        </div>`
    element.innerHTML = loader
}

/**
 * @param {HTMLButtonElement} button
 * @param {string} loadingText
 */
export const createButtonLoader = (button, loadingText = 'chargement...') => {
    button.setAttribute('disabled', 'disabled')
    button.innerHTML = `
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        ${loadingText}
    `
}

/**
 * @param {HTMLButtonElement} button
 * @param {string} text
 */
export const removeButtonLoader = (button, text) => {
    button.removeAttribute('disabled')
    button.innerHTML = text
}

/**
 * @param {HTMLInputElement} elements
 */
export const enableInput = (...elements) => {
    elements.forEach(element => {
        element.removeAttribute('disabled')
        element.setAttribute('required', 'required')
    })
}

/**
 * @param {HTMLInputElement} elements
 */
export const disableInput = (...elements) => {
    elements.forEach(element => {
        element.setAttribute('disabled', 'disabled')
        element.removeAttribute('required')
    })
}
