<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center">URL Shortener</h1>
                <br />
                <form action="">
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Enter Original URL Here"
                            v-model="url"
                        />
                        <button class="btn" v-on:click.prevent="shortenUrl">
                            Shorten URL
                        </button>
                    </div>
                </form>
                <br />
                <p v-if="notValidUrl" class="error">
                    The URL you've entered is not valid!
                </p>
                <p v-if="notSafeForBrowsing" class="warning">
                    The URL you've entered is not safe for browsing!
                </p>
            </div>
            <div v-if="loading" class="col-md-8">
                <br />
                <h3 class="text-center">Loading...</h3>
            </div>
            <div v-if="shortenedUrl" class="col-md-8">
                <br />
                <div class="input-group">
                    <label class="control-label">Your Shortened URL:</label>
                    <input
                        type="text"
                        class="form-control"
                        v-on:focus="$event.target.select()"
                        ref="shortlink"
                        readonly
                        v-model="shortenedUrl"
                    />
                    <button class="btn-copy" v-on:click.prevent="copyUrl">
                        Copy URL
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import _ from "lodash";
export default {
    data() {
        return {
            url: null,
            shortenedUrl: null,
            loading: false,
            notValidUrl: false,
            notSafeForBrowsing: false,
        };
    },

    mounted() {
        console.log("Component mounted.");
    },

    methods: {
        shortenUrl() {
            let self = this;

            self.shortenedUrl = null;
            self.notValidUrl = false;
            self.notSafeForBrowsing = false;

            if (this.isValidURL(self.url)) {
                const requestObject = {
                    client: {
                        clientId: "nasid0112",
                        clientVersion: "1.5.2",
                    },
                    threatInfo: {
                        threatTypes: ["MALWARE", "SOCIAL_ENGINEERING"],
                        platformTypes: ["ANY_PLATFORM"],
                        threatEntryTypes: ["URL"],
                        threatEntries: [
                            {
                                url: self.url,
                            },
                        ],
                    },
                };

                axios
                    .post(
                        "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=AIzaSyDzC4F-fc74kL8qCrAmynETprqdoVdg0xc",
                        requestObject
                    )
                    .then((response) => {
                        if (_.isEmpty(response.data)) {
                            self.loading = true;
                            axios
                                .post("/shorten", { url: self.url })
                                .then((response) => {
                                    self.shortenedUrl = response.data;
                                })
                                .catch((error) => {
                                    console.log(error);
                                })
                                .finally(() => {
                                    self.loading = false;
                                });
                        } else {
                            self.notSafeForBrowsing = true;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                self.notValidUrl = true;
            }
        },
        copyUrl() {
            this.$refs.shortlink.focus();
            document.execCommand("copy");
        },
        isValidURL(string) {
            let result = string.match(
                /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g
            );
            return result !== null;
        },
        isSafeForBrowsing(url) {
            const requestObject = {
                client: {
                    clientId: "nasid0112",
                    clientVersion: "1.5.2",
                },
                threatInfo: {
                    threatTypes: ["MALWARE", "SOCIAL_ENGINEERING"],
                    platformTypes: ["ANY_PLATFORM"],
                    threatEntryTypes: ["URL"],
                    threatEntries: [
                        {
                            url: url,
                        },
                    ],
                },
            };
            axios
                .post(
                    "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=AIzaSyDzC4F-fc74kL8qCrAmynETprqdoVdg0xc",
                    requestObject
                )
                .then((response) => {
                    //console.log(_.isEmpty(response.data));
                    if (_.isEmpty(response.data)) {
                        return true;
                    } else {
                        return false;
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>

<style scoped>
.btn {
    background: #074e81;
    color: white;
}
.btn-copy {
    background: #2a2b2c;
    color: white;
}
.control-label {
    padding: 8px 30px 0px 0px;
    font-weight: bold;
}
.error {
    color: red;
    font-size: 18px;
    font-weight: 500;
    text-align: center;
    border: 1px solid red;
}
.warning {
    color: rgba(219, 91, 16, 0.932);
    font-size: 18px;
    font-weight: 500;
    text-align: center;
    border: 1px solid rgba(219, 91, 16, 0.932);
}
</style>
