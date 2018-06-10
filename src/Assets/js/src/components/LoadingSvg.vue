<template>
  <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
       preserveAspectRatio="xMidYMid">
    <circle cx="50" cy="50" fill="none" r="46" stroke="#c21429" stroke-width="5">
      <animate attributeName="stroke-dasharray" calcMode="linear"
               values="0 0 0 144.51326206513048 0 144.51326206513048;0 0 144.51326206513048 0 0 144.51326206513048;0 0 144.51326206513048 0 0 144.51326206513048;0 144.51326206513048 0 144.51326206513048 0 144.51326206513048;0 144.51326206513048 0 144.51326206513048 0 144.51326206513048"
               keyTimes="0;0.2;0.4;0.6;1" dur="1.3" begin="-1.3s" repeatCount="indefinite"></animate>
    </circle>
    <circle cx="50" cy="50" fill="none" r="40" stroke="#a61123" stroke-width="5">
      <animate attributeName="stroke-dasharray" calcMode="linear"
               values="0 0 0 125.66370614359172 0 125.66370614359172;0 0 125.66370614359172 0 0 125.66370614359172;0 0 125.66370614359172 0 0 125.66370614359172;0 125.66370614359172 0 125.66370614359172 0 125.66370614359172;0 125.66370614359172 0 125.66370614359172 0 125.66370614359172"
               keyTimes="0;0.2;0.4;0.6;1" dur="1.3" begin="-1.196s" repeatCount="indefinite"></animate>
    </circle>
    <circle cx="50" cy="50" fill="none" r="34" stroke="#d89393" stroke-width="5">
      <animate attributeName="stroke-dasharray" calcMode="linear"
               values="0 0 0 106.81415022205297 0 106.81415022205297;0 0 106.81415022205297 0 0 106.81415022205297;0 0 106.81415022205297 0 0 106.81415022205297;0 106.81415022205297 0 106.81415022205297 0 106.81415022205297;0 106.81415022205297 0 106.81415022205297 0 106.81415022205297"
               keyTimes="0;0.2;0.4;0.6;1" dur="1.3" begin="-1.092s" repeatCount="indefinite"></animate>
    </circle>
    <g transform="rotate(180 50 50)">
      <circle cx="50" cy="50" fill="none" r="46" stroke="#c21429" stroke-width="5">
        <animate attributeName="stroke-dasharray" calcMode="linear"
                 values="0 0 0 144.51326206513048 0 144.51326206513048;0 0 144.51326206513048 0 0 144.51326206513048;0 0 144.51326206513048 0 0 144.51326206513048;0 144.51326206513048 0 144.51326206513048 0 144.51326206513048;0 144.51326206513048 0 144.51326206513048 0 144.51326206513048"
                 keyTimes="0;0.2;0.4;0.6;1" dur="1.3" begin="-0.572s" repeatCount="indefinite"></animate>
      </circle>
      <circle cx="50" cy="50" fill="none" r="40" stroke="#a61123" stroke-width="5">
        <animate attributeName="stroke-dasharray" calcMode="linear"
                 values="0 0 0 125.66370614359172 0 125.66370614359172;0 0 125.66370614359172 0 0 125.66370614359172;0 0 125.66370614359172 0 0 125.66370614359172;0 125.66370614359172 0 125.66370614359172 0 125.66370614359172;0 125.66370614359172 0 125.66370614359172 0 125.66370614359172"
                 keyTimes="0;0.2;0.4;0.6;1" dur="1.3" begin="-0.676s" repeatCount="indefinite"></animate>
      </circle>
      <circle cx="50" cy="50" fill="none" r="34" stroke="#d89393" stroke-width="5">
        <animate attributeName="stroke-dasharray" calcMode="linear"
                 values="0 0 0 106.81415022205297 0 106.81415022205297;0 0 106.81415022205297 0 0 106.81415022205297;0 0 106.81415022205297 0 0 106.81415022205297;0 106.81415022205297 0 106.81415022205297 0 106.81415022205297;0 106.81415022205297 0 106.81415022205297 0 106.81415022205297"
                 keyTimes="0;0.2;0.4;0.6;1" dur="1.3" begin="-0.8320000000000001s"
                 repeatCount="indefinite"></animate>
      </circle>
    </g>
  </svg>
</template>

<script>
  export default {
    name: 'loading-svg'
  };
</script>

<style lang="scss">

</style>
