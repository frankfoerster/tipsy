<template>
  <svg class="checkmark-svg" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 52 52">
    <circle class="checkmark-svg--circle" cx="26" cy="26" r="25" fill="none"></circle>
    <path class="checkmark-svg--check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
  </svg>
</template>

<script>
  export default {
    name: 'checkmark-svg'
  };
</script>

<style lang="scss" scoped>
  @import '../sass/imports';

  $curve: cubic-bezier(0.650, 0.000, 0.450, 1.000);

  .checkmark-svg {
    border-radius: 50%;
    display: block;
    stroke-width: 2px;
    stroke: #fff;
    stroke-miterlimit: 10;
    box-shadow: inset 0 0 0 $green;
    animation: checkmark-fill .4s ease-in-out .4s forwards, checkmark-scale .3s ease-in-out .9s both;
    border: 4px solid #69a937;
  }

  .checkmark-svg--circle {
    stroke-dasharray: 166px;
    stroke-dashoffset: 166px;
    stroke-width: 2px;
    stroke-miterlimit: 10;
    stroke: $green;
    fill: none;
    animation: checkmark-stroke .6s $curve forwards;
  }

  .checkmark-svg--check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48px;
    stroke-dashoffset: 48px;
    animation: checkmark-stroke .3s $curve .8s forwards;
  }

  @keyframes checkmark-stroke {
    100% {
      stroke-dashoffset: 0;
    }
  }

  @keyframes checkmark-scale {
    0%, 100% {
      transform: none;
    }
    50% {
      transform: scale3d(1.1, 1.1, 1);
    }
  }

  @keyframes checkmark-fill {
    100% {
      box-shadow: inset 0 0 0 200px $green;
    }
  }
</style>
