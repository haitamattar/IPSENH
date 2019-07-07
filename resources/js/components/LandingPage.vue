<template>
    <div class="landingPageContent">
        <div class="container">

            <div class="row intro">
                <div class="col-md-6">
                    <div class="introText">
                        <h1>
                            Too busy with programming?<br>
                            Create a portfolio in no time<br><br>

                            Blog about stuff and tutorials<br>
                            monetize some of your articles
                        </h1>
                        <router-link :to="{name: 'register'}">
                            <a href="#" class="btn btn-primary btn-lg" role="button">Create a portfolio</a>
                        </router-link>
                    </div>
                </div>
                <div class="col-md-6">
                    <img id='showcaseImg1' src="/images/showcase1.png">

                </div>
            </div>
            <div class="gitUtils">
                <h2>Connect with Github, Gitlab and Bitbucket</h2>
                <div class="row">
                    <div class="col-md-4 gitLogo">
                        <img id='gitHubUtilImg' src="/images/GitHub.png">
                    </div>
                    <div class="col-md-4 gitLogo">
                        <img id='gitLabUtilImg' src="/images/GitLab.png">
                    </div>
                    <div class="col-md-4 bitBucketLogo gitLogo">
                        <img id='BitUtilImg' src="/images/BitBucket.png">
                    </div>
                </div>
            </div>
            <img id="showcase2" src="/images/showcase2.png" alt="">
        </div>

        <div id="showUserVistors">
            <div class="container">
                <h2>Show visitors that you are interested<br> in job opportunities</h2>
                <img id="showcase3" src="/images/GroupPhoto.png" alt="">
            </div>
        </div>

    </div>


</template>

<script>

</script>
