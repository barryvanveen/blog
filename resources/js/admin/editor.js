const initEditors = () => {
  const editors = document.querySelectorAll('[data-editor]')

  editors.forEach(editor => {
    registerInputEventListener(editor)
  })
}

const registerInputEventListener = (el) => {
  el.addEventListener('input', editorInputChangedListener)
  el.dispatchEvent(new Event('input'))
}

const editorInputChangedListener = (event) => {
  const el = event.target
  el.style.height = '1px'
  el.style.height = el.scrollHeight + 'px'
}

const editor = () => {
  initEditors()
}

export default editor
