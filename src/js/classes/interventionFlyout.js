export default class InterventionFlyout {
  constructor(navInstance) {
    this.nav = navInstance
    this.el = document.getElementById('artist-flyout')
    this.opener = this.el.querySelector('#flyout-opener')
    this.closer = this.el.querySelector('#flyout-closer')
    this.video = this.el.querySelector('.vid')
    this.info = this.el.querySelector('.artist-info')

    this.init()
  }

  init () {
    this.opener.addEventListener('click', e => {
      e.preventDefault()

      this.el.classList.add('active')
      document.body.style.overflow = 'hidden'
      if (this.playButton) this.playButton.tabIndex = 0
      this.closer.tabIndex = 0
      this.opener.tabIndex = -1
      this.playButton
        ? this.playButton.focus()
        : this.closer.focus()
      this.el.ariaExpanded = true
      this.info.tabIndex = 0
    }) 

    this.closer.addEventListener('click', e => {
      e.preventDefault()

      this.close()
    })

    if (this.video) {
      this.playButton = this.el.querySelector('.video-player-button')
      this.soundButton = this.el.querySelector('.sound-player-button')
      this.playing = false

      this.playButton.addEventListener('click', e => {
        e.preventDefault()
        
        if (!this.playing) {
          this.playing = true
          this.el.classList.add('playing')
          this.soundButton.tabIndex = 0
          // this.untabEls.forEach(el => el.tabIndex = -1)
          this.video.muted = false
          this.video.play()
        } else {
          this.playing = false
          this.el.classList.remove('playing')
          this.soundButton.tabIndex = -1
          this.video.pause()
        }
      })

      this.soundButton.addEventListener('click', e => {
        e.preventDefault()

        if (this.video.muted) {
          this.video.muted = false
          this.el.classList.remove('muted')
        } else {
          this.video.muted = true
          this.el.classList.add('muted')
        }
      })
    }
  }

  close() {
    this.el.classList.remove('active')
    document.body.style.overflow = 'initial'

    this.playing = false
    this.el.classList.remove('playing')
    this.soundButton.tabIndex = -1
    // this.untabEls.forEach(el => el.tabIndex = 0)
    this.video.pause()

    if (this.playButton) this.playButton.tabIndex = -1
    this.closer.tabIndex = -1
    this.opener.tabIndex = 0
    this.info.tabIndex = -1

    this.el.ariaExpanded = false

    this.opener.focus()
  }

  hasTabLeftDropdown(e) {
    if (!this.el.contains(e.relatedTarget)) {
      this.close()
    }
  }
}
