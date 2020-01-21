<template>
    <div class="leadbinder-header-container">
        <div class="leadbinder-header">
            <h1> {{ bigName }} </h1>
        </div>
        <div class="leadbinder-header-mobile">
            <div class="small-title">
                <h1> {{ smName }} </h1>
            </div>
            <div class="menu-box">
                <button type="button"><i class="fad fa-bars"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "HeaderComponent",
        props: ['bigName', 'smName']
    }
</script>

<style scoped>
    @media screen {
        .leadbinder-header-container {
            color: #fff;
        }
    }

    @media screen and (max-width: 999px) {
        .leadbinder-header-container {
            padding: 0.25em;
        }

        .leadbinder-header {
            display: none;
        }

        .leadbinder-header-mobile {
            width: 100%;
            display: flex;
            flex-flow: row;
        }

        .small-title {
            width: 90%;
            font-size: 1em;
            text-align: center;
        }

        .menu-box {
            width: 10%;
            -webkit-box-align: center;
            text-align: center;
            margin: auto;
        }

        .menu-box button {
            background-color: transparent;
            border: 1px solid #fff;
            border-radius: 0.1rem;
        }

        .menu-box button i {
            font-size: 2em;
            color: #fff;
        }

        .menu-box button:hover {
            background-color: #DFF200;
            border: 1px solid black;
        }

        .menu-box button i:hover {
            color: black;
        }

    }

    @media screen and (min-width: 1000px) {
        .leadbinder-header-container {
            padding: 0.25em;
        }

        .leadbinder-header-mobile {
            display: none;
        }

        .leadbinder-header {
            font-size: 2em;
            text-align: center;
        }
    }
</style>
