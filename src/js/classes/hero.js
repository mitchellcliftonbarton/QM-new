import { gsap, ScrollTrigger } from 'gsap/all'
gsap.registerPlugin( ScrollTrigger )

export default class Hero {
  constructor (el) {
    this.el = el
    this.untabEls = Array.from(document.querySelectorAll('.untab-onplay'))
    this.init()
  }

  init () {
    if (this.el.classList.contains('has-vid')) {
      this.video = this.el.querySelector('.vid')
      this.playButton = this.el.querySelector('.video-player-button')
      this.soundButton = this.el.querySelector('.sound-player-button')
      this.playing = false

      this.playButton.addEventListener('click', e => {
        e.preventDefault()
        
        if (!this.playing) {
          this.playing = true
          this.el.classList.add('playing')
          this.soundButton.tabIndex = 0
          this.untabEls.forEach(el => el.tabIndex = -1)
          this.playButton.ariaLabel = `Pause ${this.playButton.dataset.labelfinish}`
          this.video.muted = false
          this.video.play()
        } else {
          this.playing = false
          this.el.classList.remove('playing')
          this.soundButton.tabIndex = -1
          this.untabEls.forEach(el => el.tabIndex = 0)
          this.playButton.ariaLabel = `Play ${this.playButton.dataset.labelfinish}`
          this.video.pause()
        }
      })

      this.soundButton.addEventListener('click', e => {
        e.preventDefault()

        if (this.video.muted) {
          this.video.muted = false
          this.el.classList.remove('muted')
          this.soundButton.ariaPressed = false
        } else {
          this.video.muted = true
          this.el.classList.add('muted')
          this.soundButton.ariaPressed = true
        }
      })
    }

    ScrollTrigger.create({
      trigger: this.el,
      start: "top top",
      end: "bottom top",
      onLeave: () => {
        this.playing = false
        this.el.classList.remove('playing')
        if (this.soundButton) this.soundButton.tabIndex = -1
        this.untabEls.forEach(el => el.tabIndex = 0)
        if (this.playButton) {
          this.playButton.ariaLabel = `Play ${this.playButton.dataset.labelfinish}`
          this.video.pause()
        }
      }
    })
  }
}