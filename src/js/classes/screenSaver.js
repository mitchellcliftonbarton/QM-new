export default class ScreenSaver {
  constructor() {
    this.inactiveTimer = null
    this.stampInterval = null
    this.el = document.getElementById('artist-screensaver')
    this.pngStamp = document.getElementById('png-stamp')
    this.imagesArrayEl = document.getElementById('screensaver_json')
    this.info = this.el.querySelector('.artist-info')

    this.init()
  }

  init() {
    if (this.imagesArrayEl) {
      this.imagesArray = JSON.parse(document.getElementById('screensaver_json').textContent)
      this.images = this.imagesArray.filter(image => image !== null)

      if (this.images.length > 0 && this.pngStamp) {
        this.reset()

        // document.addEventListener('mousemove', () => {
        //   this.reset()
        // })

        // window.addEventListener('scroll', () => {
        //   this.reset()
        // })

        this.pngStamp.addEventListener('click', e => {
          e.preventDefault()

          document.body.style.overflow = 'initial'
          this.el.classList.remove('active')
          this.el.ariaExpanded = false
          if (this.info) this.info.tabIndex = -1
          this.reset()
        }) 
      }
    }
  }

  reset() {
    clearTimeout(this.inactiveTimer)
    clearInterval(this.stampInterval)

    setTimeout(() => {
      if (this.pngStamp.hasChildNodes) {
        this.pngStamp.innerHTML = ''
      }
    }, 400)

    this.inactiveTimer = setTimeout(() => {
      document.body.style.overflow = 'hidden'
      this.el.classList.add('active')
      this.el.ariaExpanded = true
      if (this.info) {
        this.info.tabIndex = 0
        this.info.focus()
      }
      
      this.addImage()
        
      this.stampInterval = setInterval(() => {
        this.addImage()
      }, 10000) // 20 seconds
    }, 300000) // 5 minutes
  }

  getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min
  }

  addImage() {
    let image = new Image()

    const randomImage = this.images[Math.floor(Math.random()*this.images.length)]

    image.src = randomImage
    image.style.top = `${this.getRandomInt(-10, 110)}%`
    image.style.left = `${this.getRandomInt(-10, 110)}%`
    image.onload = () => {
      this.pngStamp.appendChild(image)
    }
  }
}
