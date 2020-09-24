const lambda = require('../../src/index');

describe('Test basic builder', function () {
    it('An invalid JSON triggers a 400 response', async () => {
        const event = {
            httpMethod: 'POST',
            body: '{"invalid-json",}'
        };

        const result = await lambda.handle(event);
        const response = JSON.parse(result.body);

        expect(result.statusCode).toEqual(400);
        expect(response.error).toEqual('Request does not contain a valid JSON');
    });

    it('An empty request triggers a 200 response', async () => {
        const event = {
            httpMethod: 'POST',
            body: '[]'
        };

        const result = await lambda.handle(event);

        expect(result.statusCode).toEqual(200);
    });
});
