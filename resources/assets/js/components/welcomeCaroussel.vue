<template>
  <div>
    <b-carousel id="welcomeCaroussel"
                style="text-shadow: 1px 1px 2px #333;"
                controls
                indicators
                background="#ababab"
                :interval="4000"
                img-width="1024"
                img-height="480"
                v-model="slide"
                @sliding-start="onSlideStart"
                @sliding-end="onSlideEnd"
    >

      <!-- Text slides with image -->
      <b-carousel-slide caption="CACOMA"
                        text="Nulla vitae elit libero, a pharetra augue mollis interdum."
                        img-src="http://www.black-iz.com/photos/institute-course/python-course-dhaka.jpg"
      ></b-carousel-slide>

      <!-- Slides with custom text -->
      <b-carousel-slide img-src="https://www.kitecamppro.com/wp-content/uploads/2013/06/partners-manera.jpg">
        <h1>Hello world!</h1>
      </b-carousel-slide>

      <!-- Slides with image only -->
      <b-carousel-slide img-src="https://s-media-cache-ak0.pinimg.com/originals/79/9f/e0/799fe078cf89098e410cb1845ee31739.jpg">
      </b-carousel-slide>

      <!-- Slides with img slot -->
      <!-- Note the classes .d-block and .img-fluid to prevent browser default image alignment -->
<!--       <b-carousel-slide>
        <img slot="img" class="d-block img-fluid w-100" width="1024" height="480"
             src="https://lorempixel.com/1024/480/technics/5/" alt="image slot">
      </b-carousel-slide> -->
      
<!--       
      <b-carousel-slide>
        <img src="https://st2.depositphotos.com/1001877/9682/i/950/depositphotos_96826944-stock-photo-pills-with-cobalt-co-element.jpg" alt="image slot">
      </b-carousel-slide>

      <!-- Slide with blank fluid image to maintain slide aspect ratio -->
     <!-- <b-carousel-slide caption="Blank Image" img-blank img-alt="Blank image">
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
          eros felis, tincidunt a tincidunt eget, convallis vel est. Ut pellentesque
          ut lacus vel interdum.
        </p>
      </b-carousel-slide> -->

    </b-carousel>

<!--     <p class="mt-4">
      Slide #: {{ slide }}<br>
      Sliding: {{ sliding }}
    </p>
 -->
    
<!--     <b-container fluid>
    <b-row>
      <b-col sm="3"><label for="type-datetime-local">Tipo</label></b-col>
      <b-col sm="9"><b-form-input id="type-datetime-local" type="datetime-local"></b-form-input></b-col>
    </b-row>
  </b-container> -->
<!--     <money v-model="price" v-bind="money"></money> {{price}} -->
  </div>
</template>

<script>
export default {
  data () {
    return {
      slide: 0,
      sliding: null,
      price: 123.45,
        money: {
          decimal: ',',
          thousands: '.',
          prefix: 'R$ ',
          suffix: ' #',
          precision: 2,
          masked: false
        }
      
    }
  },
  methods: {
    onSlideStart (slide) {
      this.sliding = true
    },
    onSlideEnd (slide) {
      this.sliding = false
    }
  }
}
</script>