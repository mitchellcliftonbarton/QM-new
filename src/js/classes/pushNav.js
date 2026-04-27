export default class PushNav {
  constructor () {
    this.pushNavEls = Array.from(document.querySelectorAll('.push-nav')) // get all elements that have a push-nav class on them
    this.heroEls = Array.from(document.querySelectorAll('.h-screen-minus-nav')) // get all hero elements with push-nav applied
    this.navHeight = null // get height of nav
    this.resizeTimer = null
    this.mobileSublinks = document.querySelector('.mobile-sublinks')
    this.init()
  }

  init() {
    this.setVals()

    window.addEventListener('resize', () => {
      clearTimeout(this.resizeTimer)
      this.resizeTimer = setTimeout(() => {
        this.setVals()
      }, 400)
    })
  }

  setVals() {
    this.navHeight = document.getElementById('nav').clientHeight - 1 // get nav height, minus 1 just cause
    if (this.mobileSublinks) this.heightWithMobileSublinks = this.navHeight + this.mobileSublinks.clientHeight

    // for each push nav el, apply padding top of nav height
    this.pushNavEls.forEach(el => {
      if (el.classList.contains('ignore-mobile-sublinks')) {
        el.style.paddingTop = `${this.navHeight}px`
      } else {
        if (this.mobileSublinks) {
          el.style.paddingTop = `${this.heightWithMobileSublinks}px`
        } else {
          el.style.paddingTop = `${this.navHeight}px`
        }
      }
    })

    // for each hero that is affected by push-nav, calculate height
    this.heroEls.forEach(el => {
      el.style.height = `calc(100vh - ${this.navHeight}px)`
    })
  }
}
