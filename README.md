# AI Advisor: AI Use Case Recommendation Tool

> last modified 30 July, 2026 by Colleen Diamond

The `ai-advisor` repository contains code for a web-based tool to generate recommendations for customer AI use cases based on the company profile and a short questionnaire surrounding business priorities, opportunities, and readiness. The form is to be completed by the AE following an initial discovery call with the customer, and the results are to be reviewed with the AI/DS team (Rick and Colleen) before sharing with the customer. The generated use case recommendations are intended to provide the customer with immediate, practical, and valuable AI solutions that we can realistically help them implement in order to enable ongoing AI-related development and trigger further opportunities.

The tool uses an HTML form to collect questionnaire data, with the company profile data being autopopulated from the CRM. The questionnaire data is then run through a recommendation algorithm to generate a set of potential use cases. An LLM is used to parse the text inputs, to augment the recommendation algorithm, and to refine the output.

## Code overview

## Initial setup

## Future updates
- add access token caching
- add searchable dropdown for company name