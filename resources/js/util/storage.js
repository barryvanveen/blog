const commentCreatedCookieName = 'commentCreated'

// https://developer.mozilla.org/en-US/docs/Web/API/Web_Storage_API/Using_the_Web_Storage_API#testing_for_availability
const sessionStorageAvailable = () => {
  let storage
  try {
    storage = window.sessionStorage
    const x = '__storage_test__'
    storage.setItem(x, x)
    storage.removeItem(x)
    return true
  } catch (e) {
    return e instanceof DOMException && (
      // everything except Firefox
      e.code === 22 ||
      // Firefox
      e.code === 1014 ||
      // test name field too, because code might not be present
      // everything except Firefox
      e.name === 'QuotaExceededError' ||
      // Firefox
      e.name === 'NS_ERROR_DOM_QUOTA_REACHED'
    ) &&
    // acknowledge QuotaExceededError only if there's something already stored
    (storage && storage.length !== 0)
  }
}

const setCommentCreatedCookie = () => {
  if (sessionStorageAvailable()) {
    sessionStorage.setItem(commentCreatedCookieName, 'true')
  }
}

const hasCommentCreatedCookie = () => {
  if (!sessionStorageAvailable()) {
    return false
  }
  return sessionStorage.getItem(commentCreatedCookieName) === 'true'
}

const removeCommentCreatedCookie = () => {
  if (sessionStorageAvailable()) {
    sessionStorage.removeItem(commentCreatedCookieName)
  }
}

export {
  setCommentCreatedCookie,
  hasCommentCreatedCookie,
  removeCommentCreatedCookie
}
