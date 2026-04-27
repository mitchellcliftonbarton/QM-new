import { gsap, ScrollToPlugin, ScrollTrigger } from 'gsap/all'
gsap.registerPlugin(ScrollToPlugin, ScrollTrigger)

function relisten() {
  window.scrollToSectionInstances.forEach((instance) => {
    instance.enable()
  })

  window.removeEventListener('scroll', relisten)
}

export default class ScrollToLink {
  constructor(el, state, isLargeQuery) {
    this.el = el
    this.state = state
    this.nav = document.getElementById('nav')
    this.listenEnabled = true
    this.isLargeQuery = isLargeQuery
    this.init()
  }

  init() {
    const self = this
    this.otherLinks = Array.from(document.querySelectorAll('.scroll-to-link'))

    this.el.addEventListener('click', (e) => {
      e.preventDefault()

      document.body.classList.remove('mobile-sublinks-open')

      if (this.isLargeQuery) {
        window.scrollToSectionInstances.forEach((instance) => {
          instance.disable()
        })
      }

      const target = document.getElementById(this.el.dataset.target)
      this.otherLinks.forEach((link) => link.classList.remove('active'))
      this.el.classList.add('active')

      window.history.pushState({}, '', `#${this.el.dataset.target}`)

      let isScrollingUp = window.scrollY > window.pageYOffset + target.getBoundingClientRect().top

      if (isScrollingUp) {
        this.state.scrollWatchInactive = true
      } else {
        this.state.scrollWatchInactive = false
      }

      let offset = this.nav.classList.contains('has-two-rows') ? 105 : 85

      gsap.to(window, {
        scrollTo: {
          duration: 0.5,
          y: target,
          offsetY: offset,
        },
        onComplete: () => {
          setTimeout(() => {
            this.state.scrollWatchInactive = false
          }, 100)

          if (this.isLargeQuery) {
            setTimeout(() => {
              window.addEventListener('scroll', relisten)
            }, 600)
          }
        },
      })
    })
  }
}
