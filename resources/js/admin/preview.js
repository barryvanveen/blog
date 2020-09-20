import debounce from '../util/debounce'
import getVariable from '../util/variables'

const initPreviews = () => {
  const editors = document.querySelectorAll('[data-editor]')

  editors.forEach(editor => {
    registerInputEventListener(editor)
  })
}

const registerInputEventListener = (el) => {
  const previewEl = document.querySelector(el.dataset.preview)

  if (!previewEl) {
    return
  }

  el.addEventListener('input', debounce(updatePreview, 1000))
  el.dispatchEvent(new Event('input'))
}

const getHtmlFromMarkdown = (markdown) => {
  const data = {
    markdown: markdown
  }

  const url = getVariable('markdown_to_html_url')

  return fetch(url, {
    method: 'POST',
    mode: 'same-origin',
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(data)
  })
}

const setHtmlOnPreviewElement = (html, el) => {
  const previewEl = document.querySelector(el.dataset.preview)

  if (!previewEl) {
    return
  }

  previewEl.innerHTML = html
}

const updatePreview = (event) => {
  const markdown = event.target.value
  getHtmlFromMarkdown(markdown)
    .then(response => response.json())
    .then(data => setHtmlOnPreviewElement(data.html, event.target))
    .catch(err => console.warn('Something went wrong.', err))
}

const preview = () => {
  initPreviews()
}

export default preview
