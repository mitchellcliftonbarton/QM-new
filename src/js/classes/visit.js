import { mapStyles } from './../lib/mapStyles'

export default class Visit {
  constructor() {
    this.mapsInterval = null
    this.map = null
    this.mapEl = document.getElementById('visit-map')
    this.init()
  }

  init() {
    this.mapsInterval = setInterval(() => {
      if (window.mapState.initMap) {
        clearInterval(this.mapsInterval)
        this.initMaps()
      }
    }, 200)
  }

  initMaps() {
    this.map = new google.maps.Map(this.mapEl, {
      center: { lat: 40.745914, lng: -73.8489134 },
      zoom: 15,
      fullscreenControl: false,
      streetViewControl: true,
      rotateControl: false,
      mapTypeControl: false,
      zoomControl: true,
      scaleControl: true,
      styles: mapStyles,
    })
  }
}
