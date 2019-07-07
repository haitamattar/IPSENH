<template>
    <transition name="slide-left" mode="out-in">
        <div>
            <navigation></navigation>
            <div class="container d-flex flex-column align-items-center justify-content-center">
                <h1 class="notFoundText">404, Oops page not found</h1>
                <a class="btn btn-primary roundedCorners cutomBtn1" style="width: 200px; margin-top: 50px;" href="/" role="button">
                    <i class="fas fa-cat"></i>  Go back to homepage
                </a>

            </div>
        </div>
    </transition>
</template>

<script>

    export default {
        name: "NotFound",
    };
</script>
