<div class="on-off-switch">
  <label class="switch">
    <input type="checkbox" {{ $status == 1 ? 'checked' : '' }} data-email-id="{{ $row_id }}" onclick="sendToggleRequest(this)">
    <span class="slider"></span>
  </label>
</div>



<style>
    /* CSS for the smaller switch button with green and red colors */
    .on-off-switch {
        display: inline-block;
        position: relative;
        width: 40px;
        /* Adjust the width to make it smaller */
        height: 20px;
        /* Adjust the height to make it smaller */
    }

    /* Hide default checkbox */
    .on-off-switch input {
        display: none;
    }

    /* The slider (circle) */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #dc3545;
        /* Red color for "Off" */
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 20px;
        /* Adjust the border-radius to make it rounder */
    }

    /* Rounded sliders */
    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        /* Adjust the height to make it smaller */
        width: 14px;
        /* Adjust the width to make it smaller */
        left: 2px;
        /* Adjust the left position */
        bottom: 2px;
        /* Adjust the bottom position */
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
    }

    /* When the checkbox is checked, move the slider to the right and change to green */
    .on-off-switch input:checked+.slider {
        background-color: green;
        /* Green color for "On" */
    }

    .on-off-switch input:checked+.slider:before {
        -webkit-transform: translateX(20px);
        /* Adjust the translation */
        -ms-transform: translateX(20px);
        /* Adjust the translation */
        transform: translateX(20px);
        /* Adjust the translation */
    }
</style>
