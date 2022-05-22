const path = require('path');
const fs = require('fs');
const sharp = require('sharp')

const inputDir = './resources/images/responsive-images';
const outputDir = './public/images';

const clearOutput = async () => {
    const files = fs.readdirSync(outputDir);

    for (const file of files) {
        fs.unlinkSync(`${outputDir}/${file}`);
    }
}

const output = (basename, variant, extension) => {
    return `${outputDir}/${basename}-${variant}.${extension}`;
}

const pngOptions = {
    compressionLevel: 9,
    effort: 10,
    palette: true,
};

const webpOptions = {
    effort: 6,
};

const avifOptions = {
    effort: 6,
};

const jpegOptions = {
    mozjpeg: true,
};

const resize = async (input, format, options) => {
    const basename = path.parse(input).name;
    const sharpStream = sharp({ failOn: 'none' });
    const promises = [];

    promises.push(
        sharpStream
            .clone()
            .resize(null, null, {})
            .toFormat(format, options)
            .toFile(output(basename, `original`, format))
    );

    promises.push(
        sharpStream
            .clone()
            .resize(750, 400, {
                fit: 'inside',
                withoutEnlargement: false,
            })
            .toFormat(format, options)
            .toFile(output(basename, `750`, format))
    );

    promises.push(
        sharpStream
            .clone()
            .resize(480, 300, {
                fit: 'inside',
                withoutEnlargement: false,
            })
            .toFormat(format, options)
            .toFile(output(basename, `480`, format))
    );

    promises.push(
        sharpStream
            .clone()
            .resize(320, 200, {
                fit: 'inside',
                withoutEnlargement: false,
            })
            .toFormat(format, options)
            .toFile(output(basename, `320`, format))
    );

    sharp(input).pipe(sharpStream);

    Promise.all(promises)
        .then(_ => {
            console.log(input);
        })
        .catch(err => {
            console.error('Error processing files, let\'s clean it up', err);
            try {
                fs.unlinkSync(output(basename, `original`, format));
                fs.unlinkSync(output(basename, `750`, format));
                fs.unlinkSync(output(basename, `480`, format));
                fs.unlinkSync(output(basename, `320`, format));
            } catch (e) {}
        });
}

const main = async () => {
    const files = fs.readdirSync(inputDir);

    for (const file of files) {
        const input = `${inputDir}/${file}`;

        await resize(input, 'png', pngOptions);
        // await resize(input, 'webp', webpOptions);
        // await resize(input, 'avif', avifOptions);
        // await resize(input, 'jpeg', jpegOptions);
    }
}

clearOutput(outputDir)
    .then(() => main());
