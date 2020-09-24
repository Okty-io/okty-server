exports.handle = async (event) => {
    let containerConfigs = [];

    try {
        containerConfigs = JSON.parse(event.body);
    } catch (e) {
        return buildResponse({ error: 'Request does not contain a valid JSON' }, 400)
    }

    return buildResponse({ count: containerConfigs.length }, 200)
}

function buildResponse(data, status, headers = []) {
    return {
        statusCode: status,
        body: JSON.stringify(data),
        headers: headers,
        isBase64Encoded: false
    }
}