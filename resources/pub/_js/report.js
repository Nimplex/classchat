window.report = async function (event) {
    const { target } = event;

    if (!target)
        return console.error("No target found");

    const { userId, listingId } = target.dataset;

    if (!userId && !listingId)
        return console.error("No data found");

    console.log({ userId, listingId });
}
