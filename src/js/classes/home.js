import axios from 'axios'
import 'regenerator-runtime/runtime'
import { formatInTimeZone, utcToZonedTime, zonedTimeToUtc } from 'date-fns-tz'

export default class Home {
  constructor() {
    this.timeEl = document.getElementById('time-weather')
    this.hero = document.querySelector('.hero-home')
    this.openText = document.getElementById('open-text')
    this.closeText = document.getElementById('close-text')
    this.init()
  }

  async init() {
    this.checkOpenStatus()
    this.weatherData = await this.getWeather()

    // wait for weather data and then get time, start timer
    this.setTime()
    this.hero.classList.add('loaded')

    setInterval(() => {
      this.setTime()
    }, 1000)

    let now = utcToZonedTime(new Date(), 'America/New_York')
    let today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
    let diff = now - today
    let currentSec = Math.round(diff / 1000)
    let seconds = (currentSec / 60) % 1
    let minutes = (currentSec / 3600) % 1
    let hours = (currentSec / 43200) % 1

    document.querySelector('.clock-second').style.animationDelay = `${60 * seconds * -1}s`
    document.querySelector('.clock-minute').style.animationDelay = `${3600 * minutes * -1}s`
    document.querySelector('.clock-hour').style.animationDelay = `${43200 * hours * -1}s`
  }

  showOpen() {
    this.openText.classList.add('active')
    this.openText.setAttribute('aria-hidden', false)
  }

  showClose() {
    this.closeText.classList.add('active')
    this.closeText.setAttribute('aria-hidden', false)
  }

  checkOpenStatus() {
    const openTime = document.body.dataset.openHour !== 'false' ? document.body.dataset.openHour : false
    const closeTime = document.body.dataset.closeHour !== 'false' ? document.body.dataset.closeHour : false

    if (openTime && closeTime) {
      const openTimeDate = zonedTimeToUtc(new Date(openTime), 'America/New_York')
      const closeTimeDate = zonedTimeToUtc(new Date(closeTime), 'America/New_York')

      const now = new Date()

      if (now < openTimeDate) {
        // console.log('closed before')
        this.showClose()
      } else if (now > closeTimeDate) {
        // console.log('closed after')
        this.showClose()
      } else if (now > openTimeDate && now < closeTimeDate) {
        // console.log('open')
        this.showOpen()
      } else {
        // console.log('closed')
        this.showClose()
      }
    } else {
      // console.log('closed cause its either a holiday, or there are no hours')
      this.showClose()
    }
  }

  async getWeather() {
    let params = new URLSearchParams()
    params.append('action', 'weather_check')
    params.append('zip', 11368)

    const request = await axios.post('/wp-admin/admin-ajax.php', params)
    const data = JSON.parse(request.data.data)

    const obj = {
      temp: Math.floor(data.main.temp),
      condition: data.weather[0].main,
    }

    return obj
  }

  setTime() {
    const time = formatInTimeZone(new Date(), 'America/New_York', 'MMMM do, h:mm aaa')

    // build string
    const string = `${time} EST, ${this.weatherData.temp}˚F.`

    // set in HTML
    this.timeEl.innerHTML = string
  }
}
