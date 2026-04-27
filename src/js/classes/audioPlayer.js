import { gsap, Draggable } from 'gsap/all'
gsap.registerPlugin(Draggable)

export default class AudioPlayer {
  constructor(el) {
    this.el = el
    this.audio = this.el.querySelector('audio')
    this.button = this.el.querySelector('button')
    this.timeline = this.el.querySelector('.timeline .line')
    this.indicator = this.el.querySelector('.indicator')
    this.playing = false
    this.resizeTimer = null
    this.init()
  }

  init() {
    this.button.addEventListener('click', (e) => {
      e.preventDefault()

      this.el.classList.add('started')
      if (!this.timelineWidth) this.timelineWidth = this.timeline.offsetWidth - this.indicator.offsetWidth

      if (!this.playing) {
        this.playing = true
        this.audio.play()
        this.el.classList.add('playing')
      } else {
        this.playing = false
        this.audio.pause()
        this.el.classList.remove('playing')
      }
    })

    const self = this
    gsap.set(this.indicator, { y: '-50%' })

    this.draggable = Draggable.create('.indicator', {
      type: 'x',
      bounds: '.timeline',
      onPressInit: () => {
        this.audio.pause()
      },
      onRelease: function () {
        let val = (this.x * self.audio.duration) / self.timelineWidth
        self.audio.currentTime = val
        if (self.playing) self.audio.play()
      },
    })

    this.audio.addEventListener('timeupdate', () => {
      let val = (this.audio.currentTime * this.timelineWidth) / this.audio.duration
      val = val.toFixed(2)
      gsap.to(this.indicator, { x: val, duration: 0 })
    })

    window.addEventListener('resize', () => {
      clearTimeout(this.resizeTimer)
      this.resizeTimer = setTimeout(() => {
        this.timelineWidth = this.timeline.offsetWidth - this.indicator.offsetWidth
      }, 450)
    })
  }
}
