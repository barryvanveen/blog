import { hasCommentCreatedCookie, removeCommentCreatedCookie } from '../util/storage'

const displayCommentCreatedNotice = () => {
  const notice = document.querySelector('#comment-created')
  notice.classList.remove('hidden')
  notice.scrollIntoView()
}

const initCommentCreatedNoticeDisplayer = () => {
  if (hasCommentCreatedCookie()) {
    displayCommentCreatedNotice()
    removeCommentCreatedCookie()
  }
}

const commentCreatedNoticeDisplayer = () => {
  initCommentCreatedNoticeDisplayer()
}

export default commentCreatedNoticeDisplayer
