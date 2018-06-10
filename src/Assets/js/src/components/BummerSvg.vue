<template>
  <svg class="bummer-svg" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 52 52">
    <circle class="bummer-svg--circle" cx="26" cy="26" r="25" fill="none"></circle>
    <line class="bummer-svg--cross" fill="none" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="12.9" y1="12.9" x2="39.1" y2="39.1"></line>
    <line class="bummer-svg--cross" fill="none" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="39.1" y1="12.9" x2="12.9" y2="39.1"></line>
  </svg>
</template>

<script>
  export default {
    name: 'bummer-svg'
  };
</script>

<style lang="scss" scoped>
  @import '../sass/imports';

  $curve: cubic-bezier(0.650, 0.000, 0.450, 1.000);

  .bummer-svg {
    border-radius: 50%;
    display: block;
    stroke-width: 2px;
    stroke: #fff;
    stroke-miterlimit: 10;
    box-shadow: inset 0 0 0 $rot;
    animation: bummer-fill .4s ease-in-out .4s forwards, bummer-scale .3s ease-in-out .9s both;
    border: 4px solid $rot4;
  }

  .bummer-svg--circle {
    stroke-dasharray: 166px;
    stroke-dashoffset: 166px;
    stroke-width: 2px;
    stroke-miterlimit: 10;
    stroke: $rot;
    fill: none;
    animation: bummer-stroke .6s $curve forwards;
  }

  .bummer-svg--cross {
    transform-origin: 50% 50%;
    stroke-dasharray: 48px;
    stroke-dashoffset: 48px;
    animation: bummer-stroke .3s $curve .8s forwards;
  }

  .bummer-svg--cross {
    stroke: #fff;
  }

  @keyframes bummer-stroke {
    100% {
      stroke-dashoffset: 0;
    }
  }

  @keyframes bummer-scale {
    0%, 100% {
      transform: none;
    }
    50% {
      transform: scale3d(1.1, 1.1, 1);
    }
  }

  @keyframes bummer-fill {
    100% {
      box-shadow: inset 0 0 0 200px $rot;
    }
  }
</style>
