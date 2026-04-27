import { gsap, ScrollTrigger } from 'gsap/all'
gsap.registerPlugin( ScrollTrigger )

export default class ScrollToLink {
  constructor (el, links) {
    this.el    = el
    this.trigger = null
    this.links = links
    this.init()
  }

  init () {
    this.trigger = ScrollTrigger.create({
      trigger: this.el,
      start: "top 25%",
      end: "bottom 75%",
      onEnter: () => {
        this.toggleLinks(this.el.id)
      },
      onEnterBack: () => {
        this.toggleLinks(this.el.id)
      },
      // markers: true
    })
  }

  toggleLinks (id) {
    this.links.forEach(link => link.classList.toggle('active', link.dataset.target === id))
  }

  disable() {
    this.trigger.disable()
  }

  enable() {
    this.trigger.enable()
  }
}
