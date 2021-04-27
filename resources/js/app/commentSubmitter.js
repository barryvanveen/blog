import { postJson } from '../util/fetch'
import { setCommentCreatedCookie } from '../util/storage'

const formErrorClass = 'js-error'

const initCommentSubmitter = async () => {
  const forms = document.querySelectorAll('form[name="comment"]')

  forms.forEach(form => {
    form.addEventListener('submit', submitComment)
  })
}

const submitComment = async (event) => {
  event.preventDefault()

  const form = event.target
  const submitButton = form.querySelector('input[type="submit"]')

  disableSubmitButton(submitButton)

  const formData = getFormData(form)

  clearFormErrors(form)

  postJson(form.action, formData)
    .then(() => {
      return setCommentCreatedCookie()
    })
    .then(() => {
      return redirectToLatestComment()
    })
    .catch(errorJson => {
      showFormErrors(form, errorJson, submitButton)
      enableSubmitButton(submitButton)
    })
}

const disableSubmitButton = (submitButton) => {
  submitButton.setAttribute('disabled', true)
  submitButton.classList.add('cursor-not-allowed')
}

const enableSubmitButton = (submitButton) => {
  submitButton.removeAttribute('disabled')
  submitButton.classList.remove('cursor-not-allowed')
}

const clearFormErrors = (form) => {
  const errors = form.querySelectorAll(`.${formErrorClass}`)
  errors.forEach((error) => {
    error.remove()
  })
}

const getFormData = (form) => {
  const formData = new FormData(form)

  const data = {}
  formData.forEach((value, key) => {
    data[key] = value
  })

  return data
}

const redirectToLatestComment = () => {
  return window.location.reload()
}

const showFormErrors = (form, errorJson, submitButton) => {
  errorJson.response.json()
    .then(errors => {
      // errors for individual form fields
      Object.entries(errors).forEach(([key, value]) => {
        if (key === 'error') {
          return // skip generic errors
        }
        const input = form.querySelector('[name=' + key + ']')
        if (!input) {
          console.error('Cannot find form field ' + key + ', so cannot show validation error')
          return
        }
        const error = createErrorElement(value)
        insertAfter(error, input)
      })

      if (!errors.error) {
        return true
      }

      // generic errors, like "Commenting is currently disabled"
      const formError = createErrorElement(errors.error)
      insertAfter(formError, submitButton)
      console.error(errors.error)
      return true
    })
    .catch(jsonDecodeError => {
      console.error(jsonDecodeError)
    })
}

const createErrorElement = (value) => {
  const error = document.createElement('p')
  error.className = `text-red-500 italic ${formErrorClass}`
  error.textContent = value
  return error
}

const insertAfter = (el, after) => {
  after.parentNode.insertBefore(el, after.nextSibling)
}

const commentSubmitter = () => {
  initCommentSubmitter()
}

export default commentSubmitter
