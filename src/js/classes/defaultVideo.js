import YouTubePlayer from 'youtube-player'

export default class DefaultVideo {
  constructor(el) {
    this.el = el
    this.inner = this.el.querySelector('.inner')
    this.id = this.el.dataset.ytId === 'false' ? false : this.el.dataset.ytId
    this.videoEl = this.el.querySelector('video')
    this.player = null
    this.button = this.el.querySelector('.play-video')
    this.init()
  }

  init() {
    if (this.id) {
      this.player = YouTubePlayer(this.inner)
      this.player.loadVideoById(this.id)
      this.player.pauseVideo()

      if (this.button) {
        this.button.addEventListener('click', (e) => {
          e.preventDefault()

          this.player.playVideo()
          this.el.classList.add('playing')
        })
      }
    } else if (this.videoEl) {
      this.button.addEventListener('click', (e) => {
        e.preventDefault()

        this.videoEl.play()
        this.el.classList.add('playing')
      })
    }
  }
}
