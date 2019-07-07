<template>
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container">

                <div class="modal-header">
                    <slot name="header">
                        default header
                    </slot>
                </div>
                <hr>
                <div class="modal-body">
                    <slot name="body">
                        default body
                    </slot>
                </div>
                <hr>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-primary" @click="$emit('close')">
                            close
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    module.exports = {
        data: function() {
            return {
                showModal: true
            };
        }
    };
</script>
