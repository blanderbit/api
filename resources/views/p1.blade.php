<style>
    p {
        margin: 0;
        font-size: 12px;
    }
    .logo-box {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 1px solid #4876D0;
        box-sizing: border-box;
        background: #fff;

    }
    .logo-phrase td {
        height: 50px;
        width: 50px;
        text-align: center;
        font-family:montsersemibold;
    }
    .author-box .author {
        font-family: montsersemibold;
    }
    .circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        float: left;
        margin-left: 15px;
    }
</style>

<div>
    <header>
        <div style="width: 50%; text-align: left; float: left">
            <div>
                <img src="data:image/png;base64,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" alt="">
            </div>
        </div>

        <div style="width: 49.9%; text-align: right; font-size: 12px;">
            <div class="author-box">
                <span class="author">John Smith</span>
                <br>
                Date: 20/03/2016
            </div>
        </div>
    </header>

    <div style="width: 395px; height: 100px; margin: 191px auto">
        <h2 style="margin: 0; font-family: monserrmedium; text-align: center; text-transform: uppercase; line-height: 24px; font-size: 16px;">careertest
            <span style="color: #4876D0; font-family: montsersemibold;">report</span>
        </h2>

        <p style="margin: 48px; text-align: center; font-size: 12px; font-family: monserrmedium;">based on</p>

        <div style="margin-top: 39px;">
            <div class="circle" style=" border: 2px solid #F59A9A; margin-left: 0">
                <table class="logo-phrase"><tr><td>R</td></tr></table>
            </div>

            <div class="circle" style="border: 2px solid #4876D0">
                <table class="logo-phrase"><tr><td>I</td></tr></table>
            </div>

            <div class="circle" style="border: 2px solid #77DCC1">
                <table class="logo-phrase"><tr><td>A</td></tr></table>
            </div>

            <div class="circle" style="border: 2px solid #85B1F5">
                <table class="logo-phrase"><tr><td>S</td></tr></table>
            </div>

            <div class="circle" style="border: 2px solid #FDC572">
                <table class="logo-phrase"><tr><td>E</td></tr></table>
            </div>

            <div class="circle" style="border: 2px solid #FDC572">
                <table class="logo-phrase"><tr><td>C</td></tr></table>
            </div>
        </div>

        <p style="text-align: center; margin-top: 39px; font-size: 12px; font-family: monserrmedium;">theory of Dr. John Holland</p>
    </div>
</div>
