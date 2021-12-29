const MOBILE_MENU_POPOVER_SELECTOR = '#js-mobile-menu-popover'
const MOBILE_MENU_OPEN_SELECTOR = '#js-mobile-menu-open'
const MOBILE_MENU_CLOSE_SELECTOR = '#js-mobile-menu-close'

const openMobileMenu = () => {
  const mobileMenuPopover = document.querySelector(MOBILE_MENU_POPOVER_SELECTOR)
  mobileMenuPopover.classList.remove('hidden')
}

const closeMobileMenu = () => {
  const mobileMenuPopover = document.querySelector(MOBILE_MENU_POPOVER_SELECTOR)
  mobileMenuPopover.classList.add('hidden')
}

const initMobileMenu = () => {
  const openButton = document.querySelector(MOBILE_MENU_OPEN_SELECTOR)
  const closeButton = document.querySelector(MOBILE_MENU_CLOSE_SELECTOR)

  if (!openButton || !closeButton) {
    return
  }

  openButton.addEventListener('click', openMobileMenu)
  closeButton.addEventListener('click', closeMobileMenu)
}

const mobileMenu = () => {
  initMobileMenu()
}

export default mobileMenu
