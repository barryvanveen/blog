const openMobileMenu = () => {
  const mobileMenuPopover = document.querySelector('#js-mobile-menu-popover')
  mobileMenuPopover.classList.remove('hidden')
}

const closeMobileMenu = () => {
  const mobileMenuPopover = document.querySelector('#js-mobile-menu-popover')
  mobileMenuPopover.classList.add('hidden')
}

const initMobileMenu = () => {
  const openButton = document.querySelector('#js-mobile-menu-open')
  openButton.addEventListener('click', openMobileMenu)

  const closeButton = document.querySelector('#js-mobile-menu-close')
  closeButton.addEventListener('click', closeMobileMenu)
}

const mobileMenu = () => {
  initMobileMenu()
}

export default mobileMenu
